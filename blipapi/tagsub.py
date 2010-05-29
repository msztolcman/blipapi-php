# -*- coding: utf-8 -*-
# $Id$
#
# Blip! (http://blip.pl) communication library.
# Author: Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
# Version: 0.02.10
# Copyright: (r) 2009 Marcin Sztolcman
# License: http://opensource.org/licenses/gpl-license.php GNU Public License v.2

import os.path
import re

from _utils import encode_multipart

def _validate_tag_name (tag):
    return re.match ("^[-a-zA-Z0-9_\xc4\x84\xc4\x85\xc4\x86\xc4\x87\xc4\x98\xc4\x99\xc5\x81\xc5\x82\xc5\x83\xc5\x84\xc3\x93\xc3\xb3\xc5\x9a\xc5\x9b\xc5\xbb\xc5\xbc\xc5\xb9\xc5\xba]+$",
        tag
    )

def create (**args):
    if not args.get ('name') or not _validate_tag_name (args['name']):
        raise ValueError ('Incorrect tag name or tag name missing.')

    if args.setdefault ('type', 'subscribe') not in ('all', 'ignore', 'subscribe'):
        raise ValueError ('Incorrect value for "type" argument. Should be one of: ignore, subscribe.')

    if args['type'] == 'all':
        raise ValueError ('For creating, "all" type is incorrect. Should be one of: "ignore", "subscribe".')

    return dict (
        url     = "/tag_subscriptions/%s/%s" % (args['type'], args['name']),
        method  = 'put',
    )

def read (**args):
    if args.setdefault ('type', 'subscribe') not in ('all', 'ignore', 'subscribe'):
        raise ValueError ('Incorrect value for "type" argument. Should be one of: ignore, subscribe.')

    if args['type'] != 'all':
        url = '/tag_subscriptions/' + args['type'] + 'd'
    else:
        url = '/tag_subscriptions'

    params = dict ()
    params['include']   = ','.join (args.get ('include', ''))

    return dict (
        url     = url,
        method  = 'get',
        params  = params,
    )

def delete (**args):
    if not args.get ('name') or not _validate_tag_name (args['name']):
        raise ValueError ('Incorrect tag name or tag name missing.')

    return dict (
        url     = '/tag_subscriptions/tracked/' + args['name'],
        method  = 'put',
    )

