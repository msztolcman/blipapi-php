# -*- coding: utf-8 -*-
# $Id$
#
# Blip! (http://blip.pl) communication library.
# Author: Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
# Version: 0.02.11
# Copyright: (r) 2009 Marcin Sztolcman
# License: http://opensource.org/licenses/lgpl-3.0.html The GNU Lesser General Public License, version 3.0 (LGPLv3)

def read (**args):
    """ Get notices. """

    if args.get ('user'):
        if args['user'] == '__ALL__':
            if args.get ('since_id'):
                url = '/notices/' + str (args['since_id']) + '/all_since'
            else:
                url = '/notices/all'
        else:
            if args.get ('since_id'):
                url = '/users/' + args['user'] + '/notices/' + str (args['since_id']) + '/since'
            else:
                url = '/users/' + args['user'] + '/notices'
    elif args.get ('id'):
        url = '/notices/' + str (args['id'])

    else:
        url = '/notices'
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

