import pandas as pd
import numpy as np
import tensorflow as tf
from sklearn.model_selection import train_test_split
from sklearn.preprocessing import StandardScaler
from sklearn.preprocessing import LabelEncoder
from tensorflow import keras
from tensorflow.keras import layers
import os
import html
import pydot
from tensorflow.keras.utils import plot_model

# Загрузка данных из Excel файла (замените "ваш_файл.xlsx" на путь к вашему файлу)
df = pd.read_excel("C:\\Users\\user\\Desktop\\Учеба\\Белов 2\\new_ge.xlsx")

# Разделение данных на признаки (X) и целевые переменные (y_ABC, y_XYZ)
X = df.drop(columns=["ABC", "XYZ"])  # Признаки
y_ABC = df["ABC"]  # Целевая переменная для ABC
y_XYZ = df["XYZ"]  # Целевая переменная для XYZ

# Преобразование текстовых меток классов в числовой формат
label_encoder_ABC = LabelEncoder()
y_ABC = label_encoder_ABC.fit_transform(y_ABC)

label_encoder_XYZ = LabelEncoder()
y_XYZ = label_encoder_XYZ.fit_transform(y_XYZ)

# Разделение на обучающий и тестовый наборы данных
X_train, X_test, y_ABC_train, y_ABC_test, y_XYZ_train, y_XYZ_test = train_test_split(
    X, y_ABC, y_XYZ, test_size=0.2, random_state=42
)

# Нормализация признаков
scaler = StandardScaler()
X_train = scaler.fit_transform(X_train)
X_test = scaler.transform(X_test)



# Создание нейронной сети для ABC
model_ABC = keras.Sequential([
    layers.Dense(64, activation='relu', input_shape=(X_train.shape[1],)),
    layers.Dense(32, activation='relu'),
    layers.Dense(16, activation='relu'),
    layers.Dense(len(label_encoder_ABC.classes_), activation='softmax')
])

# Компиляция модели для ABC
model_ABC.compile(optimizer='adam', loss='sparse_categorical_crossentropy', metrics=['accuracy'])

# Обучение модели для ABC
model_ABC.fit(X_train, y_ABC_train, epochs=50, batch_size=32, validation_split=0.2, verbose=0)


# Создание нейронной сети для XYZ
model_XYZ = keras.Sequential([
    layers.Dense(64, activation='relu', input_shape=(X_train.shape[1],)),
    layers.Dense(32, activation='relu'),
    layers.Dense(16, activation='relu'),
    layers.Dense(len(label_encoder_XYZ.classes_), activation='softmax')
])

# Компиляция модели для XYZ
model_XYZ.compile(optimizer='adam', loss='sparse_categorical_crossentropy', metrics=['accuracy'])

# Обучение модели для XYZ
model_XYZ.fit(X_train, y_XYZ_train, epochs=50, batch_size=32, validation_split=0.2, verbose=0)

    
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
with open('./uploads/result.txt', 'w', encoding='utf-8') as file:
    for i, number in enumerate(numbers_array, start=1):
        new_data = np.array([number])  # Пример новых данных
        new_data = scaler.transform(new_data)  # Нормализация новых данных

        # Предсказание ABC группы продукта
        predictions_ABC = model_ABC.predict(new_data, verbose=0)
        predicted_class_ABC = np.argmax(predictions_ABC)
        predicted_class_label_ABC = label_encoder_ABC.inverse_transform([predicted_class_ABC])[0]

        # Предсказание XYZ группы продукта
        predictions_XYZ = model_XYZ.predict(new_data, verbose=0)
        predicted_class_XYZ = np.argmax(predictions_XYZ)
        predicted_class_label_XYZ = label_encoder_XYZ.inverse_transform([predicted_class_XYZ])[0]

        file.write(f"Продукт {i}\n")
        file.write(f"{predicted_class_label_ABC}\n")
        file.write(f"{predicted_class_label_XYZ}\n")


   

