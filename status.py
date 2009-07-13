# -*- coding: utf-8 -*-
# $Id$
#
# Blip! (http://blip.pl) communication library.
# Author: Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
# Version: 0.02.04
# Copyright: (r) 2009 Marcin Sztolcman
# License: http://opensource.org/licenses/gpl-license.php GNU Public License v.2

import os.path

from _utils import arr2qstr, make_post_data

def create (body, picture=None):
    """ Create new status. """

    if not body:
        raise ValueError ('Status body is missing.')

    if picture and not os.path.isfile (picture):
        picture = None

    fields = {
        'status[body]':      body,
    }
    if picture:
        fields['status[picture]'] = (picture, picture, )

    data, boundary = make_post_data (fields)

    return dict (
        url         = '/statuses',
        method      = 'post',
        data        = data,
        boundary    = boundary,
    )

def read (id=None, user=None, include=None, since_id=None, limit=10, offset=0):
    """ Get info about statuses. """

    url = '/statuses'
    if user:
        user = user.lower ()
        if user == '__all__':
            if id:
                url += '/' + str (id)
                id = None
            url += '/all'
            if since_id:
                url += '_since'
                since_id = None
        else:
            url = '/users/'+ user +'/statuses'

    # dla pojedynczego usera, innego niż __all__, dodajemy id wpisu
    if id:
        url += '/' + str (id)
    if since_id:
        url += '/since'

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

