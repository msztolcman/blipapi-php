# -*- coding: utf-8 -*-

from blipapi__utils import arr2qstr

def read (include=None, limit=10):
    url = '/bliposphere'

    params = dict ()
    if limit:
        params['limit'] = str (limit)
    if include:
        params['include'] = ','.join (include)

    if params:
        url += '?' + arr2qstr (params)

    return (url, 'get', None, None)

