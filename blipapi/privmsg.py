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
    """ Create new private message. """

    if not body or not user:
        raise ValueError ('Private_message body or recipient is missing.')

    fields = {
        'private_message[body]':      body,
        'private_message[recipient]': user,
    }
    if image:
        fields['private_message[picture]'] = (image, image, )

    data, boundary = make_post_data (fields)

    return dict (
        url         = '/private_messages',
        method      = 'post',
        data        = data,
        boundary    = boundary,
    )

def read (id=None, include=None, since_id=None, limit=10, offset=0):
    """ Read user's private messages. """

    if since_id:
        url = '/private_messages/since/' + str (since_id)
    elif id:
        url = '/private_messages/' + str (id)
    else:
        url = '/private_messages'

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

