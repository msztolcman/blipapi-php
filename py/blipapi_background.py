# -*- coding: utf-8 -*-
# $Id$
#
# Blip! (http://blip.pl) communication library.
# Author: Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
# Version: 0.02.03
# Copyright: (r) 2009 Marcin Sztolcman
# License: http://opensource.org/licenses/gpl-license.php GNU Public License v.2

import os.path

from blipapi__utils import prepare_post_field

def read (user):
    """ Get specified user's background info """
    if not user:
        raise ValueError ('User name is missing.')
    return ('/users/' + str (user) + '/background', 'get', None, None)

def update (background):
    """ Update current user background """
    if not os.path.isfile (background):
        raise ValueError ('Background path is missing or file not found.')

    opts = dict ()

    multipart, boundary = prepare_post_field ('background[file]', background, is_file=True)

    opts['multipart']   = multipart
    opts['boundary']    = boundary

    return ('/background', 'post', None, opts)

def delete ():
    """ Delete current user background """
    return ('/background', 'delete', None, None)

