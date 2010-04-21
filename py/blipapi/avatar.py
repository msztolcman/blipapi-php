# -*- coding: utf-8 -*-
# $Id$
#
# Blip! (http://blip.pl) communication library.
# Author: Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
# Version: 0.02.05
# Copyright: (r) 2009 Marcin Sztolcman
# License: http://opensource.org/licenses/gpl-license.php GNU Public License v.2

import os.path

from _utils import make_post_data

def read (**args):
    """ Get info about specified user's avatar. """

    if not args.get ('user'):
        url = '/avatar'
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

    data, boundary = make_post_data ({'avatar[file]': (args['image'], args['image'],)})

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

