# -*- coding: utf-8 -*-
# $Id$
#
# Blip! (http://blip.pl) communication library.
# Author: Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
# Version: 0.02.05
# Copyright: (r) 2009 Marcin Sztolcman
# License: http://opensource.org/licenses/gpl-license.php GNU Public License v.2

from _utils import arr2qstr

def read (**args):
    """Get info about user's subscriptions (to or from user). """

    if args.setdefault ('direction', 'both') not in ('both', 'to', 'from', ''):
        raise ValueError ('Incorrect param: "direction": "%s". Allowed values: both, from, to.' % args['direction'])

    if args['direction'] == 'both':
        args['direction'] = ''

    url = '/subscriptions/' + args['direction']
    if args.get ('user'):
        url = '/users/' + args['user'] + url

    params = dict ()
    params['include']   = ','.join (args.get ('include', ''))
    params              = arr2qstr (params)

    if params:
        url += '?' + params

    return dict (
        url     = url,
        method  = 'get',
    )

def update (**args):
    """ Modify user's subscriptions. """

    if args.get ('user'):
        url = '/subscriptions/' + args['user']
    else:
        url = '/subscriptions'

    if args.get ('www'):
        args['www'] = 1
    else:
        args['www'] = 0

    if args.get ('im'):
        args['im'] = 1
    else:
        args['im'] = 0

    data = {
        'subscription[www]':    str (args['www']),
        'subscription[im]':     str (args['im']),
    }
    return dict (
        url     = url + '?' + arr2qstr (data, True),
        method  = 'put',
    )

def delete (**args):
    """ Delete subscription to specified user. """

    if not args.get ('user'):
        raise ValueError ('User is missing.')

    return dict (
        url     = '/subscriptions/' + args['user'],
        method  = 'delete',
    )

