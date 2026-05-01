#!/usr/bin/env python3
import re

filepath = '/xampp/htdocs/KAS-food-delivery/resources/views/user/order-show.blade.php'
with open(filepath, 'r') as f:
    content = f.read()

# Fix the broken replacements
content = content.replace('{{ ->driver_id', '{{ $order->driver_id ?? null }}'  )
content = content.replace('{{ ->latitude', '{{ $order->latitude ?? 14.5995 }}'  )
content = content.replace('{{ ->longitude', '{{ $order->longitude ?? 120.9842 }}'  )
content = content.replace('{{ ->delivery_address', "{{ $order->delivery_address }}"  )
content = content.replace('config(\" services.google_maps.key\\)', 'config("services.google_maps.key")')

with open(filepath, 'w') as f:
    f.write(content)
print("Fixed order-show.blade.php")

# Also fix order-create.blade.php
filepath2 = '/xampp/htdocs/KAS-food-delivery/resources/views/user/order-create.blade.php'
with open(filepath2, 'r') as f:
    content2 = f.read()

content2 = content2.replace('{{ ->', '{{ $'  )

with open(filepath2, 'w') as f:
    f.write(content2)
print("Fixed order-create.blade.php")