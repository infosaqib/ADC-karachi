@echo off
setlocal enabledelayedexpansion
set i=1
for %%f in (*.png *.jpg *.jpeg) do (
  ren "%%f" "dog-img-!i!%%~xf"
  set /a i+=1
)
