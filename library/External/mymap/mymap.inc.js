//================================================================================
// MyMap - LGPL Copyright (c) 2006 Lionel Laské
//
// This file is part of MyMap.
//
// MyMap is free software; you can redistribute it and/or modify
// it under the terms of the GNU Lesser General Public License as published by
// the Free Software Foundation; either version 2.1 of the License, or
// (at your option) any later version.
//
// MyMap is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU Lesser General Public License for more details.
//
// You should have received a copy of the GNU Lesser General Public License
// along with MyMap; if not, write to the Free Software
// Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
//
//================================================================================

function MyMapInclude(file) {
  var script  = document.createElement('script');
  script.src  = file;
  script.type = 'text/javascript';
  document.getElementsByTagName('head').item(0).appendChild(script);
}

