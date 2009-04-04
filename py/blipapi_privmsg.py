# -*- coding: utf-8 -*-
# $Id$
#
# Blip! (http://blip.pl) communication library.
# Author: Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
# Version: 0.02.04
# Copyright: (r) 2009 Marcin Sztolcman
# License: http://opensource.org/licenses/gpl-license.php GNU Public License v.2

import os.path

from blipapi__utils import arr2qstr, prepare_post_field

def create (body=None, user=None, picture=None):
    if not body or not user:
        raise ValueError ('Private_message body or recipient is missing.')

    opts = dict ()
    whole_multipart = ''

    multipart, boundary = prepare_post_field ('private_message[body]', str (body), end=False)
    whole_multipart = multipart

    multipart, boundary = prepare_post_field ('private_message[recipient]', str (user), boundary=boundary, end=False)
    whole_multipart += "\r\n" + multipart

    if picture and os.path.isfile (picture):
        multipart, boundary = prepare_post_field ('private_message[picture]', str (picture), boundary=boundary, is_file=True, end=False)
        whole_multipart += "\r\n" + multipart

    ## dirty hack
    opts['multipart'] = whole_multipart.rstrip () + "--\r\n"

    opts['boundary'] = boundary

    return ('/private_messages', 'post', None, opts)

def read (id=None, user=None, include=None, since_id=None, limit=10, offset=0):
    url = '/private_messages'
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
            url = '/users/'+ user +'/private_messages'

    if id:
        url += '/' + str (id)
    if since_id:
        url += '/since'

    params = dict ()

    if limit:
        params['limit'] = str (limit)
    if offset:
        params['offset'] = str (offset)
    if include:
        params['include'] = ','.join (include)

    if params:
        url += '?' + arr2qstr (params)

    return (url, 'get', None, None)

def delete (id):
    if not id:
        raise ValueError ('Private_message ID is missing.')

    return ('/private_messages/' + str (id), 'delete', None, None)

