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

def create (body, user, image=None):
    """ Create new directed message. """

    if not body or not user:
        raise ValueError ('Directed_message body or recipient is missing.')

    fields = {
        'directed_message[body]':      body,
        'directed_message[recipient]': user,
    }
    if image:
        fields['directed_message[picture]'] = (image, image, )

    data, boundary = make_post_data (fields)

    return dict (
        url         = '/directed_messages',
        method      = 'post',
        data        = data,
        boundary    = boundary,
    )

def read (id=None, user=None, include=None, since_id=None, limit=10, offset=0):
    """ Read directed messages to specified or logged user, or by ID. """

    if user:
        if user == '__ALL__':
            if since_id:
                url = '/directed_messages/' + str (since_id) + '/all_since'
            else:
                url = '/directed_messages/all'
        else:
            if since_id:
                url = '/users/' + user + '/directed_messages/' + str (since_id) + '/since'
            else:
                url = '/users/' + user + '/directed_messages'
    elif id:
        url = '/directed_messages/' + str (id)

    else:
        url = '/directed_messages'
        if since_id:
            url += str (since_id) + '/since'

    params = dict ()

    if limit:
        params['limit']     = limit
    if offset:
        params['offset']    = offset
    if include:
        params['include']   = ','.join (include)

    if params:
        url += '?' + arr2qstr (params)

    return dict (
        url     = url,
        method  = 'get',
    )

def delete (id):
    """ Delete directed message. """

    if not id:
        raise ValueError ('Directed_message ID is missing.')

    return dict (
        url     = '/directed_messages/' + str (id),
        method  = 'delete',
    )

