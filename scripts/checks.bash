#!/usr/bin/env bash

case "${OSTYPE,,}" in
  cygwin|msys)
    PLATFORM="windows";;
  darwin*)
    PLATFORM="mac";;
  linux*)
    PLATFORM="linux";;
  *bsd*|solaris*)
    PLATFORM="unix";;
  haiku)
    out "<91>Sorry, BeOS doesn't like virtualization :("
    exit 1;;
  *)
    logo
    out ""
    out "It seems your OS Platform doesn't define the OSTYPE variable, or it's an unexpected value."
    out "Try setting OSTYPE to a known type within the following matches:"
    out "  <32>Windows: <90>cygwin, msys<0>"
    out "  <32>MacOS: <90>darwin*<0>"
    out "  <32>Linux: <90>linux*<0>"
    out "  <32>Unix: <90>*bsd*, solaris*<0>"
    out ""
    out "<33>Usage:<0> OSTYPE=<type> $0 $*"
    out ""
    out "<35>Example:<0> OSTYPE=FreeBSD $0 $*"
    exit 1
    ;;
esac

islinux(){ [ "$PLATFORM" = "linux" ]; }
isunix(){ [ "$PLATFORM" = "unix" ] || [ "$PLATFORM" = "linux" ] || [ "$PLATFORM" = "mac" ]; }
ismac(){ [ "$PLATFORM" = "mac" ]; }
iswindows(){ [ "$PLATFORM" = "windows" ]; }
is64(){ [ "$(uname -m)" = "x86_64" ]; }