# -*- coding: utf-8 -*-
# $Id$
#
# Blip! (http://blip.pl) communication library.
# Author: Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
# Version: 0.02.03
# Copyright: (r) 2009 Marcin Sztolcman
# License: http://opensource.org/licenses/gpl-license.php GNU Public License v.2

import copy
import httplib
import os, os.path

from blipapi__utils import arr2qstr

class BlipApi (object):
    _root      = 'api.blip.pl'

    ## uagent
    def __uagent_get (self):
        return self._uagent
    def __uagent_set (self, uagent):
        self._uagent = str (uagent)
    def __uagent_del (self):
        self._uagent = ''
    uagent = property (__uagent_get, __uagent_set, __uagent_del)

    ## referer
    def __referer_get (self):
        return self._referer
    def __referer_set (self, referer):
        self._referer = str (referer)
    def __referer_del (self):
        self._referer = ''
    referer = property (__referer_get, __referer_set, __referer_del)

    ## parser
    def __parser_get (self):
        return self._parser
    def __parser_set (self, parser):
        self._parser[parser[0]] = parser[1]
    def __parser_del (self):
        raise TypeError ('Cannot clear parsers!')
    parser = property (__parser_get, __parser_set, __parser_del)

    ## debug
    def __debug_get (self):
        return self._debug
    def __debug_set (self, level):
        if not type (level) is int or level < 0:
        	level = 10
        self._debug = level
        self._ch.set_debuglevel (level)
    def __debug_del (self):
        self.debug = 0
    debug = property (__debug_get, __debug_set, __debug_del)

    def __init__ (self, login=None, passwd=None, dont_connect=False):
        self._login     = login
        self._password  = passwd
        self._uagent    = 'BlipApi.py/0.02.03 (http://blipapi.googlecode.com)'
        self._referer   = 'http://urzenia.net'
        self._format    = 'application/json'
        self._debug     = False
        self._headers   = {
            'Accept':       self._format,
            'X-Blip-API':   '0.02',
        }

        try:
            import json
            json_parser = json.loads
        except ImportError:
            try:
                import cjson
                json_parser = cjson.decode
            except ImportError:
                json_parser = eval

        self._parsers = {
            'application/json': json_parser,
        }

        self._ch = httplib.HTTPConnection (self._root, port=httplib.HTTP_PORT)

        if not dont_connect:
            self.connect ()

    def connect (self, login=None, passwd=None):
        if login is not None:
            self._login = login
        if passwd is not None:
            self._password = passwd

        if self._login and self._password is not None:
            import base64
            self._headers['Authorization'] = 'Basic '+base64.b64encode (self._login + ':' + self._password)

    def __call__ (self, fn, *args, **kwargs):
        return getattr (self, fn) (*args, **kwargs)

    def __execute (self, method, args, kwargs):
        url, method, data, opts = method (*args, **kwargs)

        l_headers = copy.deepcopy (self._headers)
        if self.uagent:
            l_headers['User-Agent'] = self.uagent
        if self.referer:
        	l_headers['Referer']    = self.referer

        if data:
            data = arr2qstr (data)
        else:
        	data = ''

        if opts:
            if 'headers' in opts:
                l_headers.update (opts['headers'])
            if 'multipart' in opts:
                data = opts['multipart']
                l_headers['Content-Type'] = 'multipart/form-data; boundary="' + opts['boundary'] + '"'

        l_headers['Content-Length'] = len (data)

        self._ch.request (method.upper (), url, body=data, headers=l_headers)
        response = self._ch.getresponse ()

        body_parsed = False
        body        = response.read ()
        if response.status in (200, 201, 204):
        	try:
        	    body = self._parsers['application/json'] (body)
        	except:
        	    raise
        	else:
        	    body_parsed = True

        return dict (
            headers     = dict ((k.lower (), v) for k, v in response.getheaders ()),
            body        = body,
            body_parsed = body_parsed,
            status_code = response.status,
            status_body = response.reason,
        )

    def __getattr__ (self, fn):
        if '_' not in fn:
            raise AttributeError ('Command not found')

        module, method = fn.split ('_', 1)

        try:
            module = __import__ ('blipapi_' + module)
            method = getattr (module, method)
            if not callable (method):
            	raise AttributeError ()
        except:
            raise AttributeError ('Command not found')

        return lambda *args, **kwargs: self.__execute (method, args, kwargs)

