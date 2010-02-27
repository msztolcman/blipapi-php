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
        if user == '__ALL__':
            if since_id:
                url = '/notices/' + str (since_id) + '/all_since'
            else:
                url = '/notices/all'
        else:
            if since_id:
                url = '/users/' + user + '/notices/' + str (since_id) + '/since'
            else:
                url = '/users/' + user + '/notices'
    elif id:
        url = '/notices/' + str (id)

    else:
        url = '/notices'
        if since_id:
            url += '/since/' + str (since_id)

    params = dict ()

    if limit:
        params['limit']     = limit
    if offset:
        params['offset']    = offset
    if include:
        params['include']   = ','.join (include)

    if params:
        url += '?' + arr2qstr (params)

    return dict (
        url     = url,
        method  = 'get',
    )

