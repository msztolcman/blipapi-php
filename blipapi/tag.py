# -*- coding: utf-8 -*-
# $Id$
#
# Blip! (http://blip.pl) communication library.
# Author: Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
# Version: 0.02.11
# Copyright: (r) 2009 Marcin Sztolcman
# License: http://opensource.org/licenses/lgpl-3.0.html The GNU Lesser General Public License, version 3.0 (LGPLv3)

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

    return dict (
        url     = url,
        method  = 'get',
        params  = params,
    )

