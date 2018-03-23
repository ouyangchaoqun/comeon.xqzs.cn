/**
 * Created by pc on 2017/5/27.
 */
var COOKIE_BASE_NAME="come_"+cookieVersion+"_";

var cookie_base = {
    set: function (name, value, days) {
        var d = new Date;
        d.setTime(d.getTime() + 24 * 60 * 60 * 1000 * days);
        window.document.cookie =   name + "=" + value + ";path=/;expires=" + d.toGMTString();
    },
    get: function (name) {
        var v = window.document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
        return v ? v[2] : null;
    },
    delete: function (name) {
        this.set(name, '', -1);
    }
};
var cookie = {
    set: function (name, value, days) {
        cookie_base.set(COOKIE_BASE_NAME+ name,value,days)
    },
    get: function (name) {
        return  cookie_base.get(COOKIE_BASE_NAME+ name)
    },
    delete: function (name) {
        this.set(name, '', -1);
    }
};
