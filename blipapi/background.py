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

def read (**args):
    """ Get specified user's background info. """

    if not args.get ('user'):
        url = '/background'
    else:
        url = '/users/' + args['user'] + '/background'

    return dict (
        url     = '/users/' + args['user'] + '/background',
        method  = 'get',
    )

def update (**args):
    """ Update current user background. """

    if not args.get ('image') or not os.path.isfile (args['image']):
        raise ValueError ('Background path is missing or file not found.')

    data, boundary = encode_multipart ({ 'background[file]': (args['image'], args['image'], ) })

    return dict (
        url         = '/background',
        method      = 'post',
        boundary    = boundary,
        data        = data,
    )

def delete ():
    """ Delete current user background. """

    return dict (
        url     = '/background',
        method  = 'delete',
    )

