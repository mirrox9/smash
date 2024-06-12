import pandas as pd
import numpy as np
import tensorflow as tf
from sklearn.model_selection import train_test_split
from sklearn.preprocessing import StandardScaler
from sklearn.preprocessing import LabelEncoder
from tensorflow import keras
from tensorflow.keras import layers

# Загрузка данных из CSV файла ii_file.csv
file_path = 'ii_file.csv'
data = pd.read_csv(file_path)

# Загрузка model_ABC.h5 и model_XYZ.h5
model_ABC = keras.models.load_model('model_ABC.h5')
model_XYZ = keras.models.load_model('model_XYZ.h5')

# Прогнозирование для каждого набора из ii_file.csv

# Предсказание ABC группы продукта для каждого набора
X_ABC = data.values.reshape(-1, 1)  # Пример предполагаемой подготовки данных, замените на вашу
predictions_ABC = model_ABC.predict(X_ABC)
data['ABC_Predictions'] = predictions_ABC.argmax(axis=1) + 1  # Предполагается, что ABC имеет 3 класса

# Предсказание XYZ группы продукта для каждого набора
X_XYZ = data.values.reshape(-1, 1)  # Пример предполагаемой подготовки данных, замените на вашу
predictions_XYZ = model_XYZ.predict(X_XYZ)
data['XYZ_Predictions'] = predictions_XYZ.argmax(axis=1) + 1  # Предполагается, что XYZ имеет 3 класса

# Вывод результата
print(data[['ABC_Predictions', 'XYZ_Predictions']])
