// Alias de selectores generales
var getById = document.getElementById.bind(document)
var querySel = document.querySelector.bind(document)
var querySelAll = document.querySelectorAll.bind(document)




export {
    getById as _$,
    querySel as _$$,
    querySelAll as _$$$
}