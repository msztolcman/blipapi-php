# -*- coding: utf-8 -*-

from blipapi__utils import arr2qstr

def read (user, include=None):
    if not user:
        raise ValueError ('User name is missing.')

    url = '/users/' + str (user)

    params = dict ()

    if include:
        params['include'] = ','.join (include)

    if params:
        url += '?' + arr2qstr (params)

    return (url, 'get', None, None)

