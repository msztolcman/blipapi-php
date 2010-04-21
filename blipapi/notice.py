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
    params              = arr2qstr (params)

    if params:
        url += '?' + params

    return dict (
        url     = url,
        method  = 'get',
    )

