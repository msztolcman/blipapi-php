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

    direction = direction.lower ()
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

def update (user, www=None, im=None):
    """ Modify user's subscriptions. """

    url = '/subscriptions/' + user

    data = {
        'subscription[www]': str (1 if www else 0),
        'subscription[im]': str (1 if im else 0),
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

