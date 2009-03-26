# -*- coding: utf-8 -*-

from blipapi__utils import arr2qstr

def read (since_id=None, limit=None, offset=None):
    if since_id:
        url = '/shortlinks/' + str (since_id) + '/all_since'
    else:
        url = '/shortlinks/all'

    params = dict ()

    if limit:
        params['limit'] = str (limit)
    if offset:
        params['offset'] = str (offset)

    if params:
        url += '?' + arr2qstr (params)

    return (url, 'get', None, None)

