# -*- coding: utf-8 -*-
# $Id$
#
# Blip! (http://blip.pl) communication library.
# Author: Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
# Version: 0.02.10
# Copyright: (r) 2009 Marcin Sztolcman
# License: http://opensource.org/licenses/gpl-license.php GNU Public License v.2

import os.path

from _utils import encode_multipart

def read (**args):
    """ Get info about specified user's avatar. """

    if not args.get ('user'):
        url = '/avatar'
    elif args.get ('url_only'):
        size = args.get ('size', 'standard')
        if size not in ('femto', 'nano', 'pico', 'standard', 'large'):
            raise ValueError ('Unrecognized size of avatar')

        return dict (
            just_return = 'http://blip.pl/users/' + args.get ('user') + '/avatar/' + size + '.jpg'
        )
    else:
        url = '/users/' + args['user'] + '/avatar'

    return dict (
        url     = url,
        method  = 'get',
    )

def update (**args):
    """ Update current user avatar. """

    if not args.get ('image') or not os.path.isfile (args['image']):
        raise ValueError ('Avatar path missing or file not found.')

    data, boundary = encode_multipart ({'avatar[file]': (args['image'], args['image'],)})

    return dict (
        url         = '/avatar',
        method      = 'post',
        boundary    = boundary,
        data        = data,
    )

def delete ():
    """ Delete current user avatar. """

    return dict (
        url     = '/avatar',
        method  = 'delete',
    )

