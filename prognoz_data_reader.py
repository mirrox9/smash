import pandas as pd
import numpy as np  # Добавляем библиотеку numpy для работы с NaN

# Чтение данных из файла Excel
df = pd.read_excel("Z:\\home\\localhost\\www\\SMASH\\uploads\\prognoz.xlsx")

with open('uploads/prognoz_data.txt', 'w') as f:
    # Проход по каждому столбцу (показателю)
    for column in df.columns:
        # Запись названия показателя
        f.write(column + ":\n")
        # Запись значений показателя за каждый месяц
        for index, value in df[column].items():
            # Проверка на пустоту
            if pd.isnull(value):
                f.write("0\n")  # Запись нуля вместо "nan"
            else:
                f.write(f"{value}\n")
        f.write("\n")  # Добавление пустой строки между показателями