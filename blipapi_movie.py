# -*- coding: utf-8 -*-

def read (id):
    if not id:
        raise ValueError ('Update ID is missing.')

    return ('/updates/' + str (id) + '/movie', 'get', None, None)

