# -*- coding: utf-8 -*-
# $Id$
#
# Blip! (http://blip.pl) communication library.
# Author: Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
# Version: 0.02.05
# Copyright: (r) 2009 Marcin Sztolcman
# License: http://opensource.org/licenses/gpl-license.php GNU Public License v.2

from _utils import arr2qstr

def read (id=None, user=None, include=None, since_id=None, limit=10, offset=0):
    """ Get notices. """

    if user:
        if user == '__all__':
            if id:
                url += '/' + str (id)
            url += '/all'
            if since_id:
                url += '_since'
        else:
            url = '/users/' + str (user) + '/notices'
            if since_id and id:
                url += '/' + str (id) + '/since'
    else:
        if id and since_id:
            url += '/since/' + str (id)
        elif id:
            url += '/' + str (id)

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

