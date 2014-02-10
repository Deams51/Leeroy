#!/bin/sh
VEID=$1
VPS="/vz/private/"$VEID

du -sm ${VPS} | sed -r "s/^([0-9\.]+).+/\1/"


#exit `du -sm ${VPS} | sed -r "s/^([0-9\.]+).+/\1/"`