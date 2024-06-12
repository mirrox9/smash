filename = './uploads/ii_file.txt'  # Укажите имя вашего файла

# Открываем файл для чтения
with open(filename, 'r') as file:
    # Инициализируем массив для хранения чисел
    numbers_array = []

    # Читаем файл построчно
    for line in file:
        # Разбиваем строку по запятой и преобразуем каждый элемент в число
        numbers = [int(num) for num in line.strip().split(',')]
        
        # Добавляем числа в массив
        numbers_array.append(numbers)

# Выводим полученный массив
print(numbers_array[1])
