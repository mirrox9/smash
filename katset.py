import pandas as pd

def categorize_product(row):
    A = B = C = 0

    if row['Продажи'] > 24999:
        A += 1
    elif 8999 < row['Продажи'] < 25000:
        B += 1
    else:
        C += 1

    if row['Прибыль'] > 9999:
        A += 1
    elif 2699 < row['Прибыль'] < 10000:
        B += 1
    else:
        C += 1

    if row['Маржа'] > 29:
        A += 1
    elif 9 < row['Маржа'] < 30:
        B += 1
    else:
        C += 1

    if row['Количество продаж'] > 149:
        A += 1
    elif 59 < row['Количество продаж'] < 150:
        B += 1
    else:
        C += 1

    if row['Доля рынка'] > 5:
        A += 1
    elif 1 < row['Доля рынка'] < 6:
        B += 1
    else:
        C += 1

    if A > B and A > C:
        return "A"
    elif B > A and B > C:
        return "B"
    else:
        return "C"

def xyz(row):
    X = Y = Z = 0
 
    if row['Коэффициент конверсии'] > 3:
        X += 1
    elif 1.49 < row['Коэффициент конверсии'] < 2.9:
        Y += 1
    else:
        Z += 1

    if row['Среднее время жизни клиента'] > 1.9:
        X += 1
    elif 0.9 < row['Среднее время жизни клиента'] < 2:
        Y += 1
    else:
        Z += 1

    if row['Частота покупок'] > 2.9:
        X += 1
    elif 1.9 < row['Частота покупок'] < 3:
        Y += 1
    else:
        Z += 1      

    if X > Y and X > Z:
        return "X"
    elif Y > X and Y > Z:
        return "Y"
    else:
        return "Z"

# Read the Excel file
file_path = 'ge.xlsx'  # Replace with your file path
df = pd.read_excel(file_path)

# Apply the categorization function to create a new column 'Category'
df['ABC'] = df.apply(categorize_product, axis=1)
df['XYZ'] = df.apply(xyz, axis=1)

# Save the updated DataFrame to a new Excel file
output_file_path = 'new_ge.xlsx'  # Replace with your desired output file path
df.to_excel(output_file_path, index=False)

print(f"Categorization completed. Results saved to {output_file_path}")
