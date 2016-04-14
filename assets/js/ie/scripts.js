$(function() {
  // Support the "placeholder" input attribute in older browsers.
  placeholder = new Placeholder();

  // Slice PC36 polyfill for nth-child
  $('.slice-pc36 .categ .grid-col:nth-child(4n+1)').addClass('resetGutter');
});