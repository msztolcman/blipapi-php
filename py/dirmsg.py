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

def create (body, user, picture=None):
    """ Create new directed message. """

    if not body or not user:
        raise ValueError ('Directed_message body or recipient is missing.')

    fields = {
        'directed_message[body]':      body,
        'directed_message[recipient]': user,
    }
    if picture:
        fields['directed_message[picture]'] = (picture, picture, )

    data, boundary = make_post_data (fields)

    return dict (
        url         = '/directed_messages',
        method      = 'post',
        data        = data,
        boundary    = boundary,
    )

def read (id=None, user=None, include=None, since_id=None, limit=10, offset=0):
    """ Read directed messages to specified or logged user, or by ID. """

    url = '/directed_messages'
    if user:
        user = user.lower ()
        if user.lower () == '__all__':
            if id:
                url += '/' + str (id)
                id = None
            url += '/all'
            if since_id:
                url += '_since'
                since_id = None
        else:
            url = '/users/' + user + '/directed_messages'

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
        url         = url,
        method      = 'get',
    )

def delete (id):
    """ Delete directed message. """

    if not id:
        raise ValueError ('Directed_message ID is missing.')

    return dict (
        url         = '/directed_messages/' + str (id),
        method      = 'delete',
    )

