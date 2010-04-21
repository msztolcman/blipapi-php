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
    """ Get statuses by tag. """

    if not args.get ('tag'):
        raise ValueError ('Tag name is missing.')

    url = '/tags/' + args['tag']

    if args.get ('since_id'):
        url += '/since/' + str (args['since_id'])

    params = dict ()
    params['limit']     = args.get ('limit', 10)
    params['offset']    = args.get ('offset', 0)
    params['include']   = ','.join (args.get ('include', ''))
    params              = arr2qstr (params)

    if params:
        url += '?' + params

    return dict (
        url     = url,
        method  = 'get',
    )

