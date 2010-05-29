# -*- coding: utf-8 -*-
# $Id$
#
# Blip! (http://blip.pl) communication library.
# Author: Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
# Version: 0.02.10
# Copyright: (r) 2009 Marcin Sztolcman
# License: http://opensource.org/licenses/gpl-license.php GNU Public License v.2

from _utils import encode_multipart

def create (**args):
    """ Create new shortened link. """

    if not args.get('link'):
        raise ValueError ('Url is missing.')

    url     = '/shortlinks'
    fields  = {
        'shortlink[original_link]': args['link']
    }

    data, boundary = encode_multipart (fields)

    return dict (
        url         = url,
        method      = 'post',
        data        = data,
        boundary    = boundary,
    )

def read (**args):
    """ Get list of shortlinks, or info about specified shortlink (by it's code). """

    if args.get ('code'):
        url = '/shortlinks/' + args['code']
    elif args.get ('since_id'):
        url = '/shortlinks/' + str (args['since_id']) + '/all_since'
    else:
        url = '/shortlinks/all'

    params = dict ()
    params['limit']     = args.get ('limit', 10)
    params['offset']    = args.get ('offset', 0)

    return dict (
        url     = url,
        method  = 'get',
        params  = params,
    )

