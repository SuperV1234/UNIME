#!/bin/bash

clang -O3 -DNDEBUG ./$1 -o /tmp/$1.temp && /tmp/$1.temp