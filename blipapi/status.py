# -*- coding: utf-8 -*-
# $Id$
#
# Blip! (http://blip.pl) communication library.
# Author: Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
# Version: 0.02.05
# Copyright: (r) 2009 Marcin Sztolcman
# License: http://opensource.org/licenses/gpl-license.php GNU Public License v.2

import os.path

from _utils import arr2qstr, make_post_data

def create (body, image=None):
    """ Create new status. """

    if not body:
        raise ValueError ('Status body is missing.')

    fields = {
        'status[body]': body,
    }

    if image and os.path.isfile (image):
        fields['status[picture]'] = (image, image, )

    data, boundary = make_post_data (fields)

    return dict (
        url         = '/statuses',
        method      = 'post',
        data        = data,
        boundary    = boundary,
    )

def read (id=None, user=None, include=None, since_id=None, limit=10, offset=0):
    """ Get info about statuses. """

    if user:
        if user == '__ALL__':
            if since_id:
                url = '/statuses/' + str (since_id) + '/all_since'
            else:
                url = '/statuses/all'
        else:
            if since_id:
                url = '/users/' + user + '/statuses/' + str (since_id) + '/since'
            else:
                url = '/users/' + user + '/statuses'

    elif id:
        url = '/statuses/' + str (id)

    else:
        url = '/statuses'
        if since_id:
            url += '/' + str (since_id) + '/since'

    params = dict ()

    if limit:
        params['limit'] = limit
    if offset:
        params['offset'] = offset
    if include:
        params['include'] = ','.join (include)

    if params:
        url += '?' + arr2qstr (params)

    return dict (
        url     = url,
        method  = 'get',
    )

def delete (id):
    """ Delete status. """

    if not id:
        raise ValueError ('Status ID is missing.')

    return dict (
        url     = '/statuses/' + str (id),
        method  = 'delete',
    )

