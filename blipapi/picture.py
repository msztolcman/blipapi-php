# -*- coding: utf-8 -*-
# $Id$
#
# Blip! (http://blip.pl) communication library.
# Author: Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
# Version: 0.02.10
# Copyright: (r) 2009 Marcin Sztolcman
# License: http://opensource.org/licenses/gpl-license.php GNU Public License v.2

def read (**args):
    """ Get info about picture from specified picture. """

    if args.get ('since_id'):
        url = '/pictures/' + str (args['since_id']) + '/all_since'
    elif args.get ('id'):
        url = '/updates/' + str (args['id']) + '/pictures'
    else:
        url = '/pictures/all'

    params = dict ()
    params['limit']     = args.get ('limit', 10)
    params['offset']    = args.get ('offset', 0)
    params['include']   = ','.join (args.get ('include', ''))

    return dict (
        url     = url,
        method  = 'get',
        params  = params,
    )

