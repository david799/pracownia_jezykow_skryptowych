import tkinter as tk
from tkinter.filedialog import askopenfilename

class IndesignReplaceApp(tk.Frame):

    def __init__(self, master=None):
        tk.Frame.__init__(self, master)
        self.pack()
        self.createWidgets()
        self.indd_file_path = None
        self.excel_file_path = None
        self.skip_white_spaces = True
    
    def createWidgets(self):
        self.enter_indd = tk.Entry(self)
        self.indd_entry_text = tk.StringVar()
        self.enter_indd["textvariable"] = self.indd_entry_text
        self.enter_indd.grid(row=0)

        self.enter_excel = tk.Entry(self)
        self.excel_entry_text = tk.StringVar()
        self.enter_excel["textvariable"] = self.excel_entry_text
        self.enter_excel.grid(row=1)

        self.choose_indd = tk.Button(self)
        self.choose_indd.grid(row=0, column=1)
        self.choose_indd["text"] = "Choose indd"
        self.choose_indd["command"] = self.choose_indd_file

        self.choose_excel = tk.Button(self)
        self.choose_excel.grid(row=1, column=1)
        self.choose_excel["text"] = "Choose excel"
        self.choose_excel["command"] = self.choose_excel_file

        self.white_spaces_label = tk.Label(self)
        self.white_spaces_label.grid(row=3)
        self.white_spaces_label["text"] = "Skip white spaces"

        self.white_spaces_toggle = tk.Button(self)
        self.white_spaces_toggle.grid(row=3, column=1)
        self.white_spaces_toggle["text"] = "Yes"
        self.white_spaces_toggle["command"] = self.white_spaces_change_toggle

        # just to make space
        tk.Label(self).grid(row=4)

        self.warning_label = tk.Label(self)
        self.warning_label.grid(row=5)
        self.warning_label["text"] = "Not replaced: 5/150"

        self.start_replacing = tk.Button(self)
        self.start_replacing.grid(row=5, column=1)
        self.start_replacing["text"] = "Start"
        self.start_replacing["command"] = self.start_replacing_process

    def choose_indd_file(self):
        self.indd_file_path = askopenfilename()
        self.indd_entry_text.set(self.indd_file_path)

    def choose_excel_file(self):
        self.excel_file_path = askopenfilename()
        self.excel_entry_text.set(self.excel_file_path)

    def white_spaces_change_toggle(self):
        self.skip_white_spaces = not self.skip_white_spaces
        self.white_spaces_toggle["text"] = "Yes" if self.skip_white_spaces else "No"

    def start_replacing_process(self):
        self.indd_file_path = self.indd_entry_text.get()
        self.excel_file_path = self.excel_entry_text.get()
        print("start")

root = tk.Tk()
app = IndesignReplaceApp(master=root)
app.mainloop()
root.destroy()