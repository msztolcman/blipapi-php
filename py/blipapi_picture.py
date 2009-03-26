# -*- coding: utf-8 -*-

from blipapi__utils import arr2qstr

def read (id, include=None):
    if not id:
        raise ValueError ('Update ID is missing.')

    url = '/updates/' + str (id) + '/pictures'

    params = dict ()

    if include:
        params['include'] = ','.join (include)

    if params:
        url += '?' + arr2qstr (params)

    return (url, 'get', None, None)

