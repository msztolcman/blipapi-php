# -*- coding: utf-8 -*-
# $Id$
#
# Blip! (http://blip.pl) communication library.
# Author: Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
# Version: 0.02.04
# Copyright: (r) 2009 Marcin Sztolcman
# License: http://opensource.org/licenses/gpl-license.php GNU Public License v.2

from _utils import arr2qstr

def read (user=None, include=None, direction='both'):
    direction = direction.lower ()
    if direction not in ('both', 'to', 'from'):
        raise ValueError ('Incorrect param: "direction": "{0}". Allowed values: both, from, to.'.format (direction))

    if direction == 'both':
        direction = ''

    url = '/subscriptions/' + direction
    if user:
        url = '/users/' + user + url

    params = dict ()

    if include:
        params['include'] = ','.join (include)

    if params:
        url += '?' + arr2qstr (params)

    return (url, 'get', None, None)

def update (user, www=None, im=None):
    url = '/subscriptions/' + str (user)

    data = {
        'subscription[www]': str (1 if www else 0),
        'subscription[im]': str (1 if im else 0),
    }
    return (url + '?' + arr2qstr (data), 'put', None, None)

def delete (user):
    return ('/subscriptions/' + str (user), 'delete', None, None)

