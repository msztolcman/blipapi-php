# -*- coding: utf-8 -*-

def read (id):
    if not id:
        raise ValueError ('Update ID is missing.')

    return ('/users/' + str (id) + '/recording', 'get', None, None)

