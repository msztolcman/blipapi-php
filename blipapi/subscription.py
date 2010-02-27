# -*- coding: utf-8 -*-
# $Id$
#
# Blip! (http://blip.pl) communication library.
# Author: Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
# Version: 0.02.05
# Copyright: (r) 2009 Marcin Sztolcman
# License: http://opensource.org/licenses/gpl-license.php GNU Public License v.2

from _utils import arr2qstr

def read (user=None, include=None, direction='both'):
    """Get info about user's subscriptions (to or from user). """

    if direction not in ('both', 'to', 'from', ''):
        raise ValueError ('Incorrect param: "direction": "%s". Allowed values: both, from, to.' % direction)

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

    return dict (
        url     = url,
        method  = 'get',
    )

def update (user=None, www=None, im=None):
    """ Modify user's subscriptions. """

    if user:
        url = '/subscriptions/' + user
    else:
        url = '/subscriptions'

    if www:
        www = 1
    else:
        www = 0

    if im:
        im = 1
    else:
        im = 0

    data = {
        'subscription[www]':    str (www),
        'subscription[im]':     str (im),
    }
    return dict (
        url     = url + '?' + arr2qstr (data),
        method  = 'put',
    )

def delete (user):
    """ Delete subscription to specified user. """

    return dict (
        url     = '/subscriptions/' + user,
        method  = 'delete',
    )

