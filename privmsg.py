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

def create (body=None, user=None, picture=None):
    if not body or not user:
        raise ValueError ('Private_message body or recipient is missing.')

    fields = {
        'private_message[body]':      body,
        'private_message[recipient]': user,
    }
    if picture:
        fields['private_message[picture]'] = (str (picture), str (picture),)

    data, boundary = make_post_data (fields)

    return dict (
        url         = '/private_messages',
        method      = 'post',
        data        = data,
        boundary    = boundary,
    )

def read (id=None, user=None, include=None, since_id=None, limit=10, offset=0):
    url = '/private_messages'
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
            url = '/users/'+ user +'/private_messages'

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
    if not id:
        raise ValueError ('Private_message ID is missing.')

    return dict (
        url         = '/private_messages/' + str (id),
        method      = 'delete',
    )

