cmake_minimum_required(VERSION 2.8)

set(CMAKE_BUILD_TYPE Release CACHE STRING "Build type.")
project(VeeLib)

set(CMAKE_C_FLAGS "-std=c99 -Wall -Wextra -W -pedantic" CACHE STRING "" FORCE)
set(CMAKE_C_FLAGS_RELEASE "-DNDEBUG -O3" CACHE STRING "" FORCE)
set(CMAKE_C_FLAGS_DEBUG "-fno-omit-frame-pointer -g3 -O0" CACHE STRING "" FORCE)
set(CMAKE_MODULE_PATH "${CMAKE_SOURCE_DIR}/cmake/modules/;${CMAKE_MODULE_PATH}")

set(SRC_DIR "src")
set(INC_DIR "include")
file(GLOB_RECURSE SRC_LIST "${INC_DIR}/*" "${SRC_DIR}/*")

include_directories(${INC_DIR})
add_library(HEADER_ONLY_TARGET STATIC ${SRC_LIST})
set_target_properties(HEADER_ONLY_TARGET PROPERTIES LINKER_LANGUAGE C)

install(DIRECTORY ${INC_DIR} DESTINATION .)
