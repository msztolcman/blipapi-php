# -*- coding: utf-8 -*-
# $Id$
#
# Blip! (http://blip.pl) communication library.
# Author: Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
# Version: 0.02.05
# Copyright: (r) 2009 Marcin Sztolcman
# License: http://opensource.org/licenses/gpl-license.php GNU Public License v.2

import os.path

from _utils import arr2qstr, make_post_data

def create (body, user=None, picture=None, private=False):
    """ Create new update. """

    if not body:
        raise ValueError ('Update body is missing.')

    if user:
        if private:
            private = '>'
        else:
            private = ''

        body = u'>%s%s %s' % (private, user, body)

    if picture and not os.path.isfile (picture):
        picture = None


    fields = {
        'update[body]':      body,
    }
    if picture:
        fields['update[picture]'] = (picture, picture, )

    data, boundary = make_post_data (fields)

    return dict (
        url         = '/updates',
        method      = 'post',
        data        = data,
        boundary    = boundary,
    )

def read (id=None, user=None, include=None, since_id=None, limit=10, offset=0):
    """ Read updates. """

    url = '/updates'
    if user:
        user = user.lower ()
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
        params['limit'] = limit
    if offset:
        params['offset'] = offset
    if include:
        params['include'] = ','.join (include)

    if params:
        url += '?' + arr2qstr (params)

    return dict (
        url     = url,
        method  = 'get',
    )

def delete (id):
    """ Delete update. """

    if not id:
        raise ValueError ('Update ID is missing.')

    return dict (
        url     = '/updates/' + str (id),
        method  = 'delete',
    )

