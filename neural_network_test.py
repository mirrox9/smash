import pandas as pd
import numpy as np
import tensorflow as tf
from sklearn.model_selection import train_test_split
from sklearn.preprocessing import StandardScaler
from sklearn.preprocessing import LabelEncoder
from tensorflow import keras
from tensorflow.keras import layers
import os

# Загрузка данных из Excel файла (замените "ваш_файл.xlsx" на путь к вашему файлу)
df = pd.read_excel("C:\\Users\\user\\Desktop\\Учеба\\Белов\\new_ge.xlsx")

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

# Оценка модели на тестовых данных для ABC
#test_loss_ABC, test_accuracy_ABC = model_ABC.evaluate(X_test, y_ABC_test)
#print(f"Точность на тестовых данных для ABC: {test_accuracy_ABC}")

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

# Оценка модели на тестовых данных для XYZ
#test_loss_XYZ, test_accuracy_XYZ = model_XYZ.evaluate(X_test, y_XYZ_test)
#print(f"Точность на тестовых данных для XYZ: {test_accuracy_XYZ}")

# Прогнозирование классов для новых данных (замените на свои данные)
new_data = np.array([[2116, 1000, 20, 10, 30, 2, 10, 3]])  # Пример новых данных
new_data = scaler.transform(new_data)  # Нормализация новых данных

# Предсказание ABC группы продукта
predictions_ABC = model_ABC.predict(new_data)
predicted_class_ABC = np.argmax(predictions_ABC)
predicted_class_label_ABC = label_encoder_ABC.inverse_transform([predicted_class_ABC])[0]
print(f"Предсказанная ABC группа продукта: {predicted_class_label_ABC}")

# Предсказание XYZ группы продукта
predictions_XYZ = model_XYZ.predict(new_data)
predicted_class_XYZ = np.argmax(predictions_XYZ)
predicted_class_label_XYZ = label_encoder_XYZ.inverse_transform([predicted_class_XYZ])[0]
print(f"Предсказанная XYZ группа продукта: {predicted_class_label_XYZ}")

