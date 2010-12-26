# -*- coding: utf-8 -*-
# $Id$
#
# Blip! (http://blip.pl) communication library.
# Author: Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
# Version: 0.02.11
# Copyright: (r) 2009 Marcin Sztolcman
# License: http://opensource.org/licenses/lgpl-3.0.html The GNU Lesser General Public License, version 3.0 (LGPLv3)

import os.path

from _utils import encode_multipart

def create (**args):
    """ Create new private message. """

    if not args.get ('body') or not args.get ('user'):
        raise ValueError ('Private_message body or recipient is missing.')

    fields = {
        'private_message[body]':      args['body'],
        'private_message[recipient]': args['user'],
    }
    if args.get ('image') and os.path.isfile (args['image']):
        fields['private_message[picture]'] = (args['image'], args['image'], )

    data, boundary = encode_multipart (fields)

    return dict (
        url         = '/private_messages',
        method      = 'post',
        data        = data,
        boundary    = boundary,
    )

def read (**args):
    """ Read user's private messages. """

    if args.get ('since_id'):
        url = '/private_messages/since/' + str (args['since_id'])
    elif args.get ('id'):
        url = '/private_messages/' + str (args['id'])
    else:
        url = '/private_messages'

    params = dict ()
    params['limit']     = args.get ('limit', 10)
    params['offset']    = args.get ('offset', 0)
    params['include']   = ','.join (args.get ('include', ''))

    return dict (
        url         = url,
        method      = 'get',
        params  = params,
    )

def delete (**args):
    """ Delete specified private message. """

    if not args.get ('id'):
        raise ValueError ('Private_message ID is missing.')

    return dict (
        url         = '/private_messages/' + str (args['id']),
        method      = 'delete',
    )

