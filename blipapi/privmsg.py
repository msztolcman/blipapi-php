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

def create (body=None, user=None, picture=None):
    """ Create new private message. """

    if not body or not user:
        raise ValueError ('Private_message body or recipient is missing.')

    fields = {
        'private_message[body]':      body,
        'private_message[recipient]': user,
    }
    if picture:
        fields['private_message[picture]'] = (picture, picture, )

    data, boundary = make_post_data (fields)

    return dict (
        url         = '/private_messages',
        method      = 'post',
        data        = data,
        boundary    = boundary,
    )

def read (id=None, include=None, since_id=None, limit=10, offset=0):
    """ Read user's private messages. """

    url = '/private_messages'

    if since_id:
        url += '/since'
    if id:
        url += '/' + str (id)

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
    """ Delete specified private message. """

    if not id:
        raise ValueError ('Private_message ID is missing.')

    return dict (
        url         = '/private_messages/' + str (id),
        method      = 'delete',
    )

