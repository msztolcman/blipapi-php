# -*- coding: utf-8 -*-
# $Id$
#
# Blip! (http://blip.pl) communication library.
# Author: Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
# Version: 0.02.05
# Copyright: (r) 2009 Marcin Sztolcman
# License: http://opensource.org/licenses/gpl-license.php GNU Public License v.2

def read (**args):
    """ Read movie data from specified update. """

    if not args.get ('id'):
        raise ValueError ('Update ID is missing.')

    return dict (
        url     = '/updates/' + str (args['id']) + '/movie',
        method  = 'get',
    )

