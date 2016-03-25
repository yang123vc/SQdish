function(b) {
  b.preventDefault();
  var a = $(this).attr("href");
  if (!~a.indexOf("http://")) {
    a = "http://passport.mafengwo.cn" + a
  }
  parent.window.location = a
}
function() {
  $(this).attr("src", "/api.php/verifyCode/?" + new Date().getTime())
}
g.handle = h = function(a) {
  return typeof p != "undefined" && (!a || p.event.triggered !== a.type) ? p.event.dispatch.apply(h.elem, arguments) : b
}
function() {
  if (b(e).find('input[type="submit"]').hasClass("disabled")) {
    return false
  }
  c.verifyFields();
  return c.manageSubmit()
}
g.handle = h = function(a) {
  return typeof p != "undefined" && (!a || p.event.triggered !== a.type) ? p.event.dispatch.apply(h.elem, arguments) : b
}