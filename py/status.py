# -*- coding: utf-8 -*-
# $Id$
#
# Blip! (http://blip.pl) communication library.
# Author: Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
# Version: 0.02.04
# Copyright: (r) 2009 Marcin Sztolcman
# License: http://opensource.org/licenses/gpl-license.php GNU Public License v.2

import os.path

from _utils import arr2qstr, prepare_post_field

def create (body, picture=None):
    if not body:
        raise ValueError ('Status body is missing.')

    if picture and not os.path.isfile (picture):
        picture = None

    opts = dict ()
    whole_multipart = ''

    multipart, boundary = prepare_post_field ('status[body]', body, end=not picture)
    whole_multipart = multipart

    if picture:
        multipart, boundary = prepare_post_field ('status[picture]', picture, boundary = boundary, is_file=True)
        whole_multipart += "\r\n" + multipart

    opts['multipart']   = whole_multipart
    opts['boundary']    = boundary

    return ('/statuses', 'post', None, opts)

def read (id=None, user=None, include=None, since_id=None, limit=10, offset=0):
    url = '/statuses'
    if user:
        user = str (user)
        if user.lower () == '__all__':
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
        raise ValueError ('Status ID is missing.')
    return ('/statuses/' + str (id), 'delete', None, None)

