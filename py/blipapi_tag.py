# -*- coding: utf-8 -*-

from blipapi__utils import arr2qstr

def read (tag, include=None, since_id=None, limit=10, offset=0):
    if not tag:
        raise ValueError ('Tag name is missing.')

    url = '/tags/' + str (tag)

    if since_id:
        url += '/since/' + str (since_id)

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

