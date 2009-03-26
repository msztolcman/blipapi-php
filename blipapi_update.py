# -*- coding: utf-8 -*-

import os.path

from blipapi__utils import arr2qstr, prepare_post_field

def create (body, user=None, picture=None):
    if not body:
        raise ValueError ('Update body is missing.')

    if user:
        body = '>{0} {1}'.format (user, body)

    if picture and not os.path.isfile (picture):
        picture = None

    opts            = dict ()
    whole_multipart = ''

    multipart, boundary     = prepare_post_field ('update[body]', body, end=not picture)
    whole_multipart         = multipart

    if picture is not None:
        multipart, boundary = prepare_post_field ('update[picture]', picture, boundary = boundary, is_file=True)
        whole_multipart     += "\r\n" + multipart

    opts['multipart']   = whole_multipart
    opts['boundary']    = boundary

    return ('/updates', 'post', None, opts)

def read (id=None, user=None, include=None, since_id=None, limit=10, offset=0):
    url = '/updates'
    if user:
        user = str (user)
        if user == '__all__':
            if id:
                url += '/' + str (id)
                id = None
            url += '/all'
            if since_id:
                url += '_since'
                since_id = None
        else:
            url = '/users/'+ user +'/updates'

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
        raise ValueError ('Update ID is missing.')

    return ('/updates/' + str (id), 'delete', None, None)

