# -*- coding: utf-8 -*-
# $Id$
#
# Blip! (http://blip.pl) communication library.
# Author: Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
# Version: 0.02.05
# Copyright: (r) 2009 Marcin Sztolcman
# License: http://opensource.org/licenses/gpl-license.php GNU Public License v.2

BLIPAPI_ALLOW_DANGEROUS_JSON    = False
BLIPAPI_MAX_REQUESTS_PER_MINUTE = 0

import copy
import httplib
import socket
import time

class BlipApiError (Exception):
    pass

class BlipApi (object):
    api_uri = 'api.blip.pl'

    ## uagent
    def __uagent_get (self):
        return self._uagent
    def __uagent_set (self, uagent):
        self._uagent = str (uagent or '')
    def __uagent_del (self):
        self._uagent = ''
    uagent = property (__uagent_get, __uagent_set, __uagent_del)

    ## referer
    def __referer_get (self):
        return self._referer
    def __referer_set (self, referer):
        self._referer = str (referer or '')
    def __referer_del (self):
        self._referer = ''
    referer = property (__referer_get, __referer_set, __referer_del)

    ## parser
    def __parser_get (self):
        return self._parser
    def __parser_set (self, parser):
        if not callable (parser):
            raise BlipApiError ('Given parser is not callable!')
        self._parser = parser
    def __parser_del (self):
        raise BlipApiError ('Cannot delete parser!')
    parser = property (__parser_get, __parser_set, __parser_del)

    ## debug
    def __debug_get (self):
        return self._debug
    def __debug_set (self, level):
        if not type (level) is int or level < 0:
            level = 0
        self._debug = level
        ## nie zawsze _ch bedzie ustawione, bledy wtedy pomijamy
        try:
            self._ch.set_debuglevel (level)
        except:
            pass

    def __debug_del (self):
        self.debug = 0
    debug = property (__debug_get, __debug_set, __debug_del)

    def _shaperd (self):
        if not BLIPAPI_MAX_REQUESTS_PER_MINUTE:
            return True

        ts = time.time ()
        self._rpm[0] += 1

        if (ts - self._rpm[1]) >= 60:
            self.shaperd_reset (_counter = 1)
            return True

        if self._rpm[0] > BLIPAPI_MAX_REQUESTS_PER_MINUTE:
            return False

        return True

    def shaperd_reset (self, _counter = 0):
        self._rpm = [_counter, time.time ()]

    def __init__ (self, login=None, passwd=None, dont_connect=False):
        self._ch        = None
        self._login     = login
        self._password  = passwd
        self._uagent    = 'BlipApi.py/0.02.05 (http://blipapi.googlecode.com)'
        self._referer   = 'http://urzenia.net/blipapi'
        self._format    = 'application/json'
        self._debug     = 0
        self._parser    = None
        self._headers   = {
            'Accept':       self._format,
            'X-Blip-API':   '0.02',
        }

        try:
            import json
            self._parser = json.loads
        except ImportError:
            try:
                import cjson
                self._parser = cjson.decode
            except ImportError:
                if BLIPAPI_ALLOW_DANGEROUS_JSON:
                    self._parser = eval

        if not dont_connect:
            self.connect ()
            ## authorize rzuci wyjatkiem jesli nie podany login lub haslo - zamiast sprawdzac te wartosci
            ## po prostu ignorujemy blad
            try:
                self.authorize ()
            except BlipApiError:
                pass

    def authorize (self, login=None, passwd=None):
        if login is not None:
            self._login = login
        if passwd is not None:
            self._password = passwd

        if self._login and self._password is not None:
            import base64
            self._headers['Authorization'] = 'Basic '+base64.b64encode (self._login + ':' + self._password)
        else:
            raise BlipApiError ('Authorization failed: missing login or password.')

    def connect (self):
        self._ch = httplib.HTTPConnection (self.api_uri, port=httplib.HTTP_PORT)
        if self._ch:
            self.shaperd_reset ()
            self._ch.set_debuglevel (self.debug)

    def __call__ (self, fn, *args, **kwargs):
        return getattr (self, fn) (*args, **kwargs)

    def __execute (self, method, args, kwargs):
        ## build request data
        req_data = method (*args, **kwargs)

        ## play with request headers
        headers = copy.deepcopy (self._headers)
        if self.uagent:
            headers['User-Agent'] = self.uagent
        if self.referer:
            headers['Referer']    = self.referer

        headers['Content-Type'] = 'multipart/form-data'
        if 'boundary' in req_data:
            headers['Content-Type'] += '; boundary="' + req_data['boundary'] + '"'
        if 'headers' in req_data:
            headers.update (req_data['headers'])

        req_body = req_data.get ('data', '')
        headers['Content-Length'] = len (req_body)

        if not self._ch:
            self.connect ()

        try:
            shaperd = self._shaperd ()
            if not shaperd:
                raise BlipApiError ('Too many requests')

            self._ch.request (req_data['method'].upper (), req_data['url'], body=req_body, headers=headers)
        except socket.error, (errno, error):
            self._ch = None
            raise BlipApiError ('Connection error: [%d] %s' % (errno, error))
        else:
            response    = self._ch.getresponse ()

        body_parsed = False
        body        = response.read ()
        if response.status in (200, 201, 204):
            ## parser errors need to be handled in higher level (by blipapi.py user)
            if body:
                body    = self._parser (body)
            else:
                body    = []
            body_parsed = True

        return dict (
            headers     = dict ((k.lower (), v) for k, v in response.getheaders ()),
            body        = body,
            body_parsed = body_parsed,
            status_code = response.status,
            status_body = response.reason,
        )

    def _import (self, name):
        mod = __import__(name)
        components = name.split('.')
        for comp in components[1:]:
            mod = getattr(mod, comp)
        return mod

    def __getattr__ (self, fn):
        if '_' not in fn:
            raise AttributeError ('Command not found.')

        module_name, method = fn.split ('_', 1)

        try:
            module = self._import ('blipapi.' + module_name)
            method = getattr (module, method)
        except Exception, e:
            raise AttributeError ('Command not found')

        if not callable (method):
            raise AttributeError ('Command not found.')

        return lambda *args, **kwargs: self.__execute (method, args, kwargs)

