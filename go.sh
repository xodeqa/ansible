#!/bin/bash

# root 用户，且配置了密码验证
ansible-playbook -i hosts site.yml

# root 用户，但未配置密码验证 需加 -k
#ansible-playbook -i hosts site.yml -k

# 非 root 用户，未配置密码验证， 且需要提升权限为 root 用户，需要加  -k --ask-sudo-pass -b
#ansible-playbook -i hosts site.yml -k --ask-sudo-pass -b
